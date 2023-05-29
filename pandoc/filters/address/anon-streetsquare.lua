function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('address')  then
  if span.classes:includes('street') then
    span.content = pandoc.Str('xxxxxxx')
    local value, position = span.classes:find('street')
    span.classes:remove(position)
    local value, position = span.classes:find('address')
    span.classes:remove(position)
  elseif span.classes:includes('square') then
    span.content = pandoc.Str('xxxxxxx')
    local value, position = span.classes:find('square')
    span.classes:remove(position)
    local value, position = span.classes:find('address')
    span.classes:remove(position)
  end
end
return span
end