function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('place') then
  if span.classes:includes('municipality') then
    span.content = pandoc.Str('xxxxx')
    local value, position = span.classes:find('municipality')
    span.classes:remove(position)
  elseif span.classes:includes('province') then
    span.content = pandoc.Str('(xx)')
    local value, position = span.classes:find('province')
    span.classes:remove(position)
  end
  local value, position = span.classes:find('place')
  span.classes:remove(position)
end
return span
end