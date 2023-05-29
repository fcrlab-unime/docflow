function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('place') and span.classes:includes('municipality') then
  span.content = pandoc.Str("xxxxx")
  local value, position = span.classes:find('municipality')
  span.classes:remove(position)
end
return span
end